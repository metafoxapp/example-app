# Example App

## Local Developement Installation Guide

1. Download zip from this repo
2. Access admincp /admincp/app and import zip file
3. Verify result
4. Copy frontend/company/note to frontend /packages

verify directory structure

```txt
- packages
-- company
---- note
```

Edit `app/settings.json" append "@company/note"

execute command before restart web server

```bash
yarn bootstrap
```

then restart 

```bash
yarn start
```
