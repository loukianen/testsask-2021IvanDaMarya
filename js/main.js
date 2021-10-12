import $ from 'jquery';

const colHeaders = [
  'Дата',
  'Начало рабочего дня',
  'Конец рабочего дня',
  'Кол-во генеральных уборок',
  'Кол-во текущих уборок',
  'Кол-во заездов',
  'Сумма оплаты за день',
];

const makeRestTableHtml = (data) => {
  const dates = Object.keys(data).sort();
  const res = dates.map((date) => {
    const localDate = new Date(date);
    const dateOptions = { year: 'numeric', month: 'short', day: 'numeric' }
    const { start, end, sum, check_in, general, current, staff } = data[date];
    const dayColHtml = (
      `<th scope="row">
        <a href="/days/?date=${date}&staff=${staff}" target="${date}">
          ${localDate.toLocaleDateString('ru-Ru', dateOptions)}
        </a>
      </th>`
    );
    const otherCols = [start, end, general, current, check_in, sum];
    return `<tr>${dayColHtml}${otherCols.map((item) => `<td>${item}</td>`).join()}</tr>`;
  });
  return res;
};

const calcSalary = (data) => {
  const dates = Object.keys(data);
  return dates.reduce((acc, day) => acc + data[day].sum, 0);
};

const renderTable = (data, node) => {
  const parsedData = JSON.parse(data);
  const tableElement = node;
  tableElement.html('');

  // Массив, в который будем складывать строки таблицы
  const tableHtmlData = [];
  // заголовок
  const colHeadersHtml = colHeaders.map((item) => `<th scope="col">${item}</th>`).join();
  tableHtmlData.push(`<thead><tr>${colHeadersHtml}</tr></thead>`);
  // строки
  tableHtmlData.push(...makeRestTableHtml(parsedData));
  // готовим таблицу
  const table = $(`<table class="table table-striped text-center border-bottom"></table>`);
  table.html(`${tableHtmlData.join(' ')}`);

  // готовим элементы с итогом за месяц
  const itogElement = $('<div></div>')
    .attr('class', 'd-flex justify-content-between p-3 font-weight-bold');
  $('<div>Итоговая сумма</div>').appendTo(itogElement);
  const salary = calcSalary(parsedData);
  $(`<div>${salary} рублей</div>`).appendTo(itogElement);

  tableElement.append(table, itogElement);
};

const run = () => {
  $(document).ready(() => {
    $('form').submit((e) => {
      e.preventDefault();

      const formData = new FormData(e.target);
      const userId = formData.get('name');
      const period = formData.get('period');

      if (userId && period) {
        $.get('/statistics', { userId, period })
          .done((data) => {
            renderTable(data, $('#maintable'));
          })
          .fail(() => console.log('Fail case'));
      }
    });
  });
};

run();
