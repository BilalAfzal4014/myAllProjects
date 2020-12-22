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