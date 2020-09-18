puts "require below";

require "/var/www/html/myAllProjects/ruby-spike/17-include-module.rb";

puts "require_relative below";

require_relative "17-include-module.rb";


#the required file will not run again(if required again anywhere in the project) like in node js