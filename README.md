## Запуск

```powershell
make init
```

Сайт: [http://localhost:8080](http://localhost:8080)

Стили лежат в `scss/main.scss`. Готовый файл уже собран в `public/assets/css/main.css`. После правки стилей его можно собрать заново командой `make css`.

`make init` копирует конфиг базы, ставит зависимости, собирает контейнеры и заполняет таблицы.

Поднять проект ещё раз, не очищая базу:

```powershell
make up
```

Заполнить таблицы заново:

```powershell
make seed
```

Если таблицы не создались, удалите данные и соберите проект с нуля:

```powershell
make reset
```

