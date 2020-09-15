class LivingThing
    attr_accessor :name, :heartRate

    def initialize(name, heartRate)
        @name = name;
        @heartRate = heartRate;
    end

    def printName()
        puts "My name is " + @name;
    end

    def printHeartRate()
        puts "My heart rate is " + @heartRate;
    end

end

class Person < LivingThing
    attr_accessor :job

    def initialize(name, heartRate, job)
        super(name, heartRate);
        @job = job;
    end

    def printJob()
        puts "My job is " + @job;
    end

    def printName()
            puts "My name is " + @name + " Afzal";
    end
end

p1 = Person.new("Bilal", "72 bpm", "Developer");

puts p1.inspect;

p1.printName();
p1.printJob();
p1.printHeartRate();