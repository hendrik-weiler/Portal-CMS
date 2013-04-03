<section class="contactform">
  <?php
    print Form::open(Uri::current());

    if(isset($company_visible) && $company_visible) 
    {
      print Form::label($company_label);
      $company_text_error = isset($company_text_error) ? $company_text_error : array();
      $company_text = isset($company_text) ? $company_text : '';
      print Form::input('company_text',$company_text,$company_text_error);
    }

    if(isset($last_name_visible) && $last_name_visible) 
    {
      print Form::label($last_name_label);
      $last_name_text_error = isset($last_name_text_error) ? $last_name_text_error : array();
      $last_name_text = isset($last_name_text) ? $last_name_text : '';
      print Form::input('last_name_text',$last_name_text,$last_name_text_error);
    }
    
    if(isset($first_name_visible) && $first_name_visible) 
    {
      print Form::label($first_name_label);
      $first_name_text_error = isset($first_name_text_error) ? $first_name_text_error : array();
      $first_name_text = isset($first_name_text) ? $first_name_text : '';
      print Form::input('first_name_text',$first_name_text,$first_name_text_error);
    }

    if(isset($postal_code_visible) && $postal_code_visible) 
    {
      print Form::label($postal_code_label);
      $postal_code_text_error = isset($postal_code_text_error) ? $postal_code_text_error : array();
      $postal_code_text = isset($postal_code_text) ? $postal_code_text : '';
      print Form::input('postal_code_text',$postal_code_text,$postal_code_text_error);
    }

    if(isset($city_visible) && $city_visible) 
    {
      print Form::label($city_label);
      $city_text_error = isset($city_text_error) ? $city_text_error : array();
      $city_text = isset($city_text) ? $city_text : '';
      print Form::input('city_text',$city_text,$city_text_error);
    }

    if(isset($phone_visible) && $phone_visible) 
    {
      print Form::label($phone_label);
      $phone_text_error = isset($phone_text_error) ? $phone_text_error : array();
      $phone_text = isset($phone_text) ? $phone_text : '';
      print Form::input('phone_text',$phone_text,$phone_text_error);
    }

    if(isset($email_visible) && $email_visible) 
    {
      print Form::label($email_label);
      $email_text_error = isset($email_text_error) ? $email_text_error : array();
      $email_text = isset($email_text) ? $email_text : '';
      print Form::input('email_text',$email_text,$email_text_error);
    }

    if(isset($text_visible) && $text_visible) 
    {
      print Form::label($text_label);
      $text_text_error = isset($text_text_error) ? $text_text_error : array();
      $text_text = isset($text_text) ? $text_text : '';
      print Form::textarea('text_text',$text_text,$text_text_error);
    }

    print Form::submit('contact_submit',__('contactform.send'));

    if(isset($success))
      print '<div class="success">' . $success . '</div>';

    print Form::close();
  ?>
</section><!-- / -->