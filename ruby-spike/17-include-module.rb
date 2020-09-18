puts "before require"

isFileIncluded = require_relative "./ruby-modules/1-module.rb";

puts "after require";

MainModuleClone = extend MainModule;
puts "after include"

#we have include a module above
#the returned thing can be used to further include and extend nested modules(s)

#if we extend the module above
#the returned thing cannot be used to further include or extend
#if we want to include or extend the nested module we have to do it from start

NestedModuleClone = include MainModuleClone::NestedModule;


DoubleNestedModuleClone = include NestedModuleClone::DoubleNestedModule;

MainModuleClone.MainModuleFun();
NestedModuleClone.NestedModuleFun();
DoubleNestedModuleClone.DoubleNestedModuleFun();



# possibilities
# MainModuleClone = include MainModule;
# NestedModuleClone = include MainModuleClone::NestedModule;
#
# MainModuleClone = include MainModule;
# NestedModuleClone = extend MainModuleClone::NestedModule;
#
# MainModuleClone = include MainModule;
# NestedModuleClone = extend MainModule::NestedModule;