<?php
/**
 * Provides more methods to fImage, most notably rotation based on EXIF data
 *   which may be present in the file.
 *
 * Requires Flourish's fImage::determineProcessor() method to be made at least
 *   protected (at the moment it is private).
 *
 * @copyright Copyright (c) 2012 bne1.
 * @author Andrew Udvare [au] <andrew@bne1.com>
 * @license http://www.opensource.org/licenses/mit-license.php
 *
 * @package Sutra
 * @link http://www.sutralib.com/
 *
 * @version 1.2
 */

namespace Sutra;

class sImage extends fImage {
  /**
   * Rotation direction upside-up.
   *
   * @var integer
   */
  const DIRECTION_UPSIDE_UP = 0;

  /**
   * Rotation direction upside-down.
   *
   * @var integer
   */
  const DIRECTION_UPSIDE_DOWN = 1;

  /**
   * Rotation direction upside-left.
   *
   * @var integer
   */
  const DIRECTION_UPSIDE_LEFT = 2;

  /**
   * Rotation direction upside-right.
   *
   * @var integer
   */
  const DIRECTION_UPSIDE_RIGHT = 3;

  /**
   * Flip direction horizontal.
   *
   * @var integer
   */
  const FLIP_HORIZONTAL = 0;

  /**
   * Flip direction vertical.
   *
   * @var integer
   */
  const FLIP_VERTICAL = 1;

  /**
   * Flip direction both ways.
   *
   * @var integer
   */
  const FLIP_BOTH = 2;

  /**
   * No flip.
   *
   * @var integer
   */
  const FLIP_NONE = 3;

  /**
   * Flip the image in a specified direction. If PECL Imagick class is
   *   not found, GD will be used.
   *
   * Unlike fImage, these changes are immediate.
   *
   * @throws fUnexpectedException If Imagick fails to return data, or if
   *   an ImagickException is thrown.
   * @throws fEnvironmentException If no image processor is found; if the image
   *   type is invalid for GD.
   *
   * Additional notes:
   * - To use ImageMagick (which is much faster), you must install the PECL
   *   Imagick extension.
   * - PJPEG is not supported if GD is used.
   * - This overwrites the data in the file before returning (this is NOT
   *  part of the operation queue).
   *
   * If the second argument is of internal type boolean, it will be treated as
   *   the $overwrite argument. The JPEG quality will be 90.
   *
   * @param integer $type One of the FLIP_* constants.
   * @param integer $jpeg_quality Because this saves changes to the file
   *   directly, if the image is JPEG, specify a quality from 0 (worst
   *   quality) to 100 (best quality). Default is 90.
   * @param boolean $overwrite If the file should be overwritten.
   * @param string $processor_override Override the processor. Must be one of:
   *   'imagemagick', 'gd'. This is generally for testing purposes only.
   * @return sImage The image object, to allow method chaining. If $overwrite
   *   is FALSE, then a new sImage object is returned.
   */
  public function flip($type, $jpeg_quality = 90, $overwrite = FALSE, $processor_override = NULL) {
    $this->tossIfDeleted();

    $file = $this;

    if ($type == self::FLIP_NONE) {
      if (!$overwrite) {
        $file = clone $this;
        $file->rename($this->getName(), FALSE);
      }
      return $file;
    }

    // Handle signature: flip($type, $overwrite = FALSE, $processor_override = NULL)
    $args = func_get_args();
    if (isset($args[1]) && is_bool($args[1])) {
      $jpeg_quality = 90;
      $overwrite = $args[1];
      $processor_override = isset($args[2]) ? (string)$args[2] : NULL;
    }

    if (!$overwrite) {
      $file = clone $this;
      $file->rename($this->getName(), FALSE);
    }

    $processor = $processor_override;
    $valid = array('gd', 'imagemagick');
    if (is_null($processor) || !in_array($processor, $valid)) {
      $processor = self::determineProcessor();
      if ($processor == 'none') {
        throw new fEnvironmentException('No image processor was found.');
      }
    }

    $mime = strtolower($file->getMimeType());
    $supported = self::getCompatibleMimeTypes();

    // Fallback to GD still if the imagick extension is not loaded
    if (is_null($processor_override)) {
      if (!extension_loaded('imagick')) {
        $processor = 'gd';
        $supported = array('image/gif', 'image/jpeg', 'image/png');
      }
      else {
        $processor = 'imagemagick';
      }
    }
    else if ($processor_override == 'gd') {
      $supported = array('image/gif', 'image/jpeg', 'image/png');
    }

    if (!in_array($mime, $supported)) {
      return $file;
    }

    if ($processor == 'imagemagick') {
      fCore::debug(__CLASS__.'->'.__FUNCTION__.': Using Imagick class.');

      $image = new Imagick($file->getPath());
      if ($type == self::FLIP_VERTICAL) {
        $image->flipImage();
      }
      else if ($type == self::FLIP_HORIZONTAL) {
        $image->flopImage();
      }
      else { // Both
        $image->flipImage();
        $image->flopImage();
      }

      try {
        $data = $image->getImageBlob();
        if (!$data) {
          throw new ImagickException('No data returned from Imagick::getImageBlob().');
        }
        $file->write($data);
        $image->clear();
        $image->destroy();
      }
      catch (ImagickException $e) {
        throw new fUnexpectedException('Caught ImagickException: '.$e->getMessage());
      }
    }
    else {
      fCore::debug(__CLASS__.'->'.__FUNCTION__.': Using GD.');

      // GD
      $img_src = NULL;

      switch ($mime) {
        case 'image/gif':
          $img_src = imagecreatefromgif($file->getPath());
          break;

        case 'image/jpeg':
          $img_src = imagecreatefromjpeg($file->getPath());
          break;

        case 'image/png':
          $img_src = imagecreatefrompng($file->getPath());
          imagealphablending($img_src, FALSE);
          imagesavealpha($img_src, TRUE);
          break;
      }

      $width = imagesx($img_src);
      $height = imagesy($img_src);
      $img_destination = imagecreatetruecolor($width, $height);
      $ret = FALSE;

      if ($mime == 'image/png') {
        imagealphablending($img_destination, FALSE);
        imagesavealpha($img_destination, TRUE);
      }

      for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
          if ($type == self::FLIP_HORIZONTAL) {
            imagecopy($img_destination, $img_src, $width - $x - 1, $y, $x, $y, 1, 1);
          }
          else if ($type == self::FLIP_VERTICAL) {
            imagecopy($img_destination, $img_src, $x, $height - $y - 1, $x, $y, 1, 1);
          }
          else if ($type == self::FLIP_BOTH) {
            imagecopy($img_destination, $img_src, $width - $x - 1, $height - $y - 1, $x, $y, 1, 1);
          }
        }
      }

      switch ($mime) {
        case 'image/gif':
          $ret = imagegif($img_destination, $file->getPath());
          break;

        case 'image/jpeg':
          $ret = imagejpeg($img_destination, $file->getPath(), $jpeg_quality);
          break;

        case 'image/png':
          $ret = imagepng($img_destination, $file->getPath());
          break;
      }

      if (!$ret) {
        throw new fUnexpectedException('Unexpected error while using GD.');
      }

      imagedestroy($img_src);
      imagedestroy($img_destination);
    }

    return $file;
  }

  /**
   * Rotate an image a certain way based on EXIF information embedded. Only
   *   JPEG and TIFF images are supported.
   *
   * Unlike fImage, these changes are immediate.
   *
   * @throws fEnvironmentException If the EXIF extension is not installed.
   *
   * @param integer $direction Optional. One of the DIRECTION_* constant
   *   values. Default is up-side up.
   * @param boolean If the file should be overwritten.
   * @param integer $jpeg_quality JPEG quality on a scale from 0 to 100.
   * @return sImage Object to allow method chaining. If $overwrite is FALSE,
   *   then a new sImage object is returned.
   */
  public function rotateAccordingToEXIFData($direction = self::DIRECTION_UPSIDE_UP, $overwrite = FALSE, $jpeg_quality = 90) {
    $file = $this;
    $file->tossIfDeleted();

    $direction = strtolower($direction);
    $mime = strtolower($this->getMimeType());

    if (!$overwrite) {
      $file = clone $file;
      $file->rename($this->getName(), FALSE);
    }

    if (!in_array($mime, array('image/tiff', 'image/jpeg'))) {
      return $file;
    }

    if (!function_exists('exif_read_data')) {
      throw new fEnvironmentException('The EXIF extension must be installed and loaded to use this method.');
    }

    // First rotate up anyways
    $exif_data = exif_read_data($this->getPath());
    $rotated = FALSE;
    $flip_type = self::FLIP_NONE;

    if (!isset($exif_data['Orientation'])) {
      return $file;
    }

    switch ($exif_data['Orientation']) {
      case 2: // Horizontal flip
        $flip_type = self::FLIP_HORIZONTAL;
        break;

      case 3: // 180 CCW
        $file->rotate(180);
        $rotated = TRUE;
        break;

      case 4: // Vertical flip
        $flip_type = self::FLIP_VERTICAL;
        break;

      case 5: // Vertical flip + 90 CCW
        $file->rotate(270);
        $rotated = TRUE;
        $flip_type = self::FLIP_VERTICAL;
        break;

      case 6: // 90 CCW
        $file->rotate(270);
        $rotated = TRUE;
        break;

      case 7: // Horizontal flip + 90 CCW
        $file->rotate(90);
        $rotated = TRUE;
        $flip_type = self::FLIP_HORIZONTAL;
        break;

      case 8: // 90 CW
        $file->rotate(270);
        $rotated = TRUE;
        break;

      default:
        return $file;
    }

    if ($rotated) {
      if ($direction != self::DIRECTION_UPSIDE_UP) {
        // Now rotate according to the direction specified
        switch ($direction) {
          case self::DIRECTION_UPSIDE_DOWN:
            $file->rotate(180);
            break;

          case self::DIRECTION_UPSIDE_LEFT:
            $file->rotate(270);
            break;

          case self::DIRECTION_UPSIDE_RIGHT:
            $file->rotate(90);
            break;
        }
      }
    }

    $file->saveChanges(NULL, $jpeg_quality, $overwrite);

    return $file->flip($flip_type, $jpeg_quality, TRUE);
  }
}

/**
 * Copyright (c) 2012 Andrew Udvare <andrew@bne1.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */
