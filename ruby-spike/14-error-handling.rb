fruits = ["Apple", "Mango", "Orange"];


begin
    fruits["abc"];
    #10 / 0;
rescue ZeroDivisionError => e
    puts e;
rescue TypeError => e
    puts e;
rescue
    puts "its some any other error";
end