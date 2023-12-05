/* Selecionando os Botões */
var btnInfo = document.getElementById('btn-info');
var btnCalendar = document.getElementById('btn-calendar');
/* Selecionando as info e calendario */
var infScore = document.getElementById('inf-score');
var calendar = document.getElementById('calendar');

btnInfo.addEventListener('click', function () {
    infScore.style.display = (infScore.style.display === 'none' || infScore.style.display === '') ? 'block' : 'none';
});

btnCalendar.addEventListener('click', function () {
    console.log("opa");
    calendar.style.display = (calendar.style.display === 'none' || calendar.style.display === '') ? 'block' : 'none';
});

