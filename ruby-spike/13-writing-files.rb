File.open("files/wFile.txt", "w") do |file| #write mode will create a new file a write init
    file.write("Amna, 24");
end

File.open("files/wFile.txt", "a") do |file| #with append mode we can write at the end of file
    file.write("\nHoorain, 0.75");
end

File.open("files/wFile.txt", "r+") do |file| #with read write mode we can read and write in the same file at the particular place
    file.readline();
    file.write("Minahil, 2\n");
end