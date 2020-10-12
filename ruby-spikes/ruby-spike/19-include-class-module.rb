require_relative "./ruby-modules/2-module.rb";

class SomeClass < Main4::Main4Class1
    extend Main1;
    include Main2;
    include Main3;
    extend Main3::Main3Nested1; #can also include but that will attached with a instance of that class
    #include Main4::Main4Class2; #cant do that
    #extend Main4::Main4Class2; #cant do that

    include Main4; #can only include that and it will be called with class name


    def main2Fun1
        puts "I am a class main function 2"
    end

    def mainFun3
        puts "I am a class main function 3"
    end
end

someObj = SomeClass.new();

SomeClass.main1Fun1();  #extended module will be on class level
someObj.main2Fun1();    #inlcluded module will be on instance level
someObj.main2Fun2();
someObj.mainFun3();
someObj.main3Fun1();
SomeClass.main3Nested1Fun1();
someObj.Main4Class1Fun1();
SomeClass::Main4Class2.new().Main4Class2Fun1();

#puts someObj.inspect;