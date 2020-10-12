
class Person  #class name will always be starts with capital letter
    attr_accessor :name, :gender, :age

    def initialize(name, gender, age)  #constructor in ruby
        @name = name;
        @gender = gender;
        @age = age;
    end

    def getGender()
        return @gender;
    end
end

p1 = Person.new("Bilal", "Male", 27);

puts p1.inspect; #print the class object
puts p1.name;
puts p1.getGender();