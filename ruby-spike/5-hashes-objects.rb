hash = {
    :name => "Bilal", #without string key
    "new york" => "city", #with string key
    "gender" => "Male"
};

hash["newKey"] = ["111"];
arr = [hash];
puts arr.inspect;
puts hash[:name]; #access without string key
puts hash["new york"]; #access with string key
puts hash["gender"];
puts hash["newKey"][0];