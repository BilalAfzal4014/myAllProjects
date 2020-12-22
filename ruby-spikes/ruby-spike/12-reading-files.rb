itr = 0;
File.open("files/rFile.txt", "r") do |file|    #opens the file and this is not loop
    puts itr;
    for line in file.readlines()
        puts line;
    end
    itr += 1;
end

#puts file.read(); # read the whole file in the memory
#file.readline(); #read the line from the current file cursor into the memory
#file.readlines(); #will read whole file and places each line in separate array index
#file.readchar(); # read the char from the current file cursor into the memory


###### if we read the file like below
puts "new way to read file";

myFile = File.open("files/rFile.txt", "r");
puts myFile.read();
myFile.close();  #have to manually close the file if we read it like that
