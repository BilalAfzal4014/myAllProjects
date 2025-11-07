const updateActivityStatusMixin = {
  methods: {
    updateActivityStatus(activityToUpdate) {
      this.activities.forEach((day) => {
        const activity = day.list.find(
          (activity) =>
            activity.schedule_detail_id === activityToUpdate.schedule_detail_id
        );

        if (activity) {
          Object.assign(activity, {
            booked_slots: activity.booked_slots + 1,
            isBooked: true,
            is_already_booked: true,
          });
        }
      });
    },
  },
};

export default updateActivityStatusMixin;
