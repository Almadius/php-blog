## Запуск

Нужны Docker Desktop, Composer и GNU Make.

```powershell
make init
```

Сайт: http://localhost:8080

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
