def foo(name, age)
    "I am " + name + ", I am " + age.to_s + " years old"; # to_s is for (whenever we use int/float in a string we need to convert it into string)
    "the last line will return automatically if doesn't have any return statement";
end

puts foo("John Doe", 73);