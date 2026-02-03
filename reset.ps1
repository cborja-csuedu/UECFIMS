Set-Location C:\xampp\htdocs\UECFIMS
Remove-Item -Recurse -Force .git
git init
git remote add origin https://github.com/cborja-csuedu/UECFIMS.git
git fetch origin main
git reset --hard origin/main
git push -u origin main
