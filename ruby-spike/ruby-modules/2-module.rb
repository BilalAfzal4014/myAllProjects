module Main1
    def main1Fun1
        puts "I am a main 1 function 1";
    end
end

module Main2
    def main2Fun1
        puts "I am a main 2 function 1";
    end

    def main2Fun2
        puts "I am a main 2 function 2";
    end
end

module Main3
    def main3Fun1
        puts "I am a main 3 function 1";
    end

    module Main3Nested1
        def main3Nested1Fun1
            puts "I am a main 3 nested 1 function 1";
        end
    end
end

Main3Module = include Main3 # have to include as well if wanted to call in the same file
Main3Module.main3Fun1();


module Main4
    class Main4Class1
        def initialize()
            puts "Main4Class1 initialize function called";
        end

        def Main4Class1Fun1
            puts "I am in main 4 class 1 function 1";
        end
    end

    class Main4Class2
        def initialize()
            puts "Main4Class2 initialize function called";
        end

        def Main4Class2Fun1
            puts "I am in main 4 class 2 function 1";
        end
    end
end