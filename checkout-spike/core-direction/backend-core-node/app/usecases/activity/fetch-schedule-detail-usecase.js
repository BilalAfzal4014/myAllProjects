const ScheduleDetailRepo = require("../../repositories/scheduleDetailRepo");

module.exports = class FetchScheduleDetailUseCase {
    static fetchScheduleDetailsById(id) {
        return ScheduleDetailRepo.findById(id);
    }

    static fetchScheduleDetailWithItsActivityById(id){
        return ScheduleDetailRepo.findWithActivityById(id);
    }
}