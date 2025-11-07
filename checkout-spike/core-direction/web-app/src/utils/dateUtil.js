const todayDate = () => new Date().toISOString();

const nextMonth = () => {
  const today = new Date();
  return new Date(
    today.getFullYear(),
    today.getMonth() + 1,
    today.getDate()
  ).toISOString();
};

export default { todayDate, nextMonth };
