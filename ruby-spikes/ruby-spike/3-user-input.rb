puts "please enter your gender";

gender = gets;   #get the input from the console, will also get \n (new line)

gender = gets.chomp() #will discard the new line

puts "my gender is " + gender + " , i am a good man";

puts "give me two numbers and i will add them";

num1 = gets.chomp().to_i; #to_i convert to integer
num2 = gets.chomp().to_f; #to_f convert to float

puts num1 + num2;