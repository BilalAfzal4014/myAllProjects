#module name must be start with a capital letter


puts "i am in 1-module";

module MainModule
    puts "I am MainModule Module";

    def MainModuleFun
        puts "I am MainModule function";
    end

    module NestedModule

        puts "I am NestedModule module";

        def NestedModuleFun
            puts "I am NestedModule function";
        end

        module DoubleNestedModule

            puts "I am DoubleNestedModule module";

            def DoubleNestedModuleFun
                puts "I am DoubleNestedModuleFun function";
            end
        end
    end
end

#modules can be used like a helper functions