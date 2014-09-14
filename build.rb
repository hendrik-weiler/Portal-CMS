
# ruby 2.00
require 'pathname'
require 'zip'
require 'fileutils'

file = File.open("buildcounter", "r")
buildnumber = file.read.to_i + 1
File.open("buildcounter", 'w') { |file| file.write(buildnumber.to_s) }
time = Time.new

date = time.strftime("%Y-%m-%d")
directory = Dir.pwd
puts directory
zipfile_name = "pcms2014-#{date}-rev#{buildnumber}.zip"

if(File.exists?(directory+"/fuel/app/INSTALL_TOOL_DISABLED"))
	File.delete(directory+"/fuel/app/INSTALL_TOOL_DISABLED")
end

if(File.exists?(directory+"/fuel/app/config/db.php.bak"))
	File.delete(directory+"/fuel/app/db.php")
	File.rename(directory+"/fuel/app/db.php.bak",directory+"/fuel/app/db.php")
end

Dir.glob(directory+"/public/cache/**").each { |f| File.delete(f) if File.file?(f) }

Dir.glob(directory+"/public/uploads/**").each { |f| 
	if(File.directory?(f)) 
		FileUtils.rm_rf(f)
	end
}

Zip::File.open(zipfile_name, Zip::File::CREATE) do |zipfile|
  Dir[File.join(directory, '**', '**')].each do |file|
  	
    path = Pathname.new(file)
    if file.include?('build.rb') == false or file.include?('buildnumber') == false
    	zipfile.add(path.relative_path_from(Pathname.new(directory)), file)
    end
  end
  zipfile.add(Pathname.new(directory+"/public/.htaccess").relative_path_from(Pathname.new(directory)), directory+"/public/.htaccess")
end