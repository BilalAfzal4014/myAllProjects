def dayTeller(dayAbb)

    day = "";

    case dayAbb
        when "Mon"
            day = "Monday";
        when "Tue"
            day = "Tuesday";
        when "Wed"
            day = "Wednesday";
        when "Thu"
            day = "Thursday";
        when "Fri"
            day = "Friday";
        when "Sat"
            day = "Saturday";
        when "Sun"
            day = "Sunday";
        else
            day = "Invalid date"
    end
        return day;
end

puts dayTeller("Fri");