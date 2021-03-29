import $ from 'jquery';

const makeRestTableHtml = (data) => {
  const dates = Object.keys(data).sort();
  const res = dates.map((date) => {
    const { start, end, sum, check_in, general, current } = data[date];
    const day = `<th scope="row"><a href="/days/?date=${date}" target="_blanc">${date}</a></th>`;
    const startDay = `<td>${start}</td>`;
    const endDay = `<td>${end}</td>`;
    const genCount = `<td>${general}</td>`;
    const curCount = `<td>${current}</td>`;
    const check_inCount = `<td>${check_in}</td>`;
    const sumAmount = `<td>${sum}</td>`;
    return `<tr>${day}${startDay}${endDay}${genCount}${curCount}${check_inCount}${sumAmount}</tr>`;
  });
  return res;
};

const run = () => {
  $(document).ready(() => {
    const mainElement = document.querySelector('main');
    // создаем элемент для таблицы и помещаем в <main>
    const tableElement = document.createElement('div');
    tableElement.setAttribute('id', '#maintable');
    mainElement.append(tableElement);
    // навешиваем слушателя на форму
    const form = document.querySelector('form');
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      // данрые из формы
      const userId = document.getElementById('name').value;
      const period = document.getElementById('period').value;

      if (userId !== 'Выберите из списка' && period) {
        // запрос на сервер
        $.get('/statistics', { userId, period })
          .done((jsonData) => {
            const data = JSON.parse(jsonData);
            // Считаем сумму за месяц
            const dates = Object.keys(data).sort();
            const salary = dates.reduce((acc, day) => acc + data[day].sum, 0);
            // чистим таблицу на случай, если это не первый запрос
            tableElement.innerHTML = null;

            const tableHtmlData = [];
            // заголовок
            tableHtmlData.push('<thead><tr><th scope="col">Дата</th><th scope="col">Начало рабочего дня</th><th scope="col">Конец рабочего дня</th><th scope="col">Кол-во генеральных уборок</th><th scope="col">Кол-во текущих уборок</th><th scope="col">Кол-во заездов</th><th scope="col">Сумма оплаты за день</th></tr></thead>');
            // строки
            tableHtmlData.push(...makeRestTableHtml(data));
            tableElement.innerHTML = `<table class="table table-striped text-center border-bottom">${tableHtmlData.join()}</table>`;
            // готовим элементы под итог за месяц и размещаем в DOM
            const itogElement = document.createElement('div');
            const itogTextElement = document.createElement('div');
            itogTextElement.textContent = 'Итоговая сумма'
            const itogSumElement = document.createElement('div');
            itogSumElement.textContent = `${salary} рублей`;
            itogElement.setAttribute('class', 'd-flex justify-content-between p-3 font-weight-bold');
            itogElement.append(itogTextElement, itogSumElement);
            tableElement.append(itogElement);
          })
          .fail(() => console.log('Fail case'));
      }
    });
  });
};

run();
