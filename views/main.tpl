<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
        <script type="text/javascript" src="/dist/bundle.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <title>Salary calculation</title>
    </head>
    <body>
      <main class="d-flex flex-column align-items-center my-5">
        <div class="h3">Расчет зарплаты горничных</div>
        <form>
          <div class="form-row">
            <div class="col-auto my-1">
              <label class="mr-sm-2" for="name">Горничная</label>
              <select class="custom-select mr-sm-2" name="name">
                <option value="">Выберите из списка</option>
                {#dataId:main}
              </select>
            </div>
            <div class="col-auto my-1">
              <label class="mr-sm-2" for="period">Период</label>
              <input type="month" value="2020-09" class="form-control mr-sm-2" name="period">
            </div>
            <div class="col-auto my-1 align-self-end">
              <button type="submit" class="btn btn-primary align-bottom">Выбрать</button>
            </div>
          </div>
        </form>
        <div id="maintable"></div>
      </main>
      <footer class="footer border-top py-3 mt-5">
        <div class="container-xl">
          <div class="text-center color-ship-border">created by Konstantin Lukyanenok</div>
        </div>
      </footer>
    </body>
    
</html>