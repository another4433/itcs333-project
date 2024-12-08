const startDate = document.getElementById("startDate");
const endDate = document.getElementById("endDate");
startDate.addEventListener("change", () => {
    const selectedDate = startDate.value;
    endDate.min = selectedDate;
});

endDate.addEventListener("change", () => {
  const selectedDate = endDate.value;
  startDate.max = selectedDate;
}) 