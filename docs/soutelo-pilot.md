# Piloto da Casa da Torre

O piloto usa uma base MySQL 8.4 dedicada, contas locais e dois grupos fictícios.
A instalação abre em [http://127.0.0.1:8088](http://127.0.0.1:8088) e o MySQL fica na rede privada do Docker.

## Instalar

É necessário Docker com Compose v2. A partir da raiz do repositório, copiar
`.env.pilot.example` para `.env.pilot`. Preencher `PILOT_APP_KEY` com
`base64:` seguido de 32 bytes aleatórios em Base64. Preencher as duas
palavras-passe de MySQL com valores aleatórios e distintos. Em Bash, usar
`openssl rand -base64 32` e `openssl rand -hex 24`, respetivamente.

```sh
export SOUTELO_PILOT_EMAIL='pilot@example.test'
read -rs -p 'Palavra-passe do piloto (pelo menos 14 caracteres) ' SOUTELO_PILOT_PASSWORD
export SOUTELO_PILOT_PASSWORD

docker compose --env-file .env.pilot -f compose.pilot.yml build app
docker compose --env-file .env.pilot -f compose.pilot.yml run --rm --user www-data app php artisan migrate --force
docker compose --env-file .env.pilot -f compose.pilot.yml run --rm --user www-data \
    -e SOUTELO_PILOT_EMAIL -e SOUTELO_PILOT_PASSWORD app php artisan db:seed --class=SouteloPilotSeeder --force
unset SOUTELO_PILOT_PASSWORD
docker compose --env-file .env.pilot -f compose.pilot.yml up -d app
```

Executar o seeder apenas na instalação inicial. O volume `pilot-mysql` guarda
os dados entre reinícios. O seeder exige a base `soutelo_pilot` vazia, gera o
primeiro utilizador local e associa-lhe o papel de administração existente.

## Experimentar

1. Entrar com o e-mail e a palavra-passe definidos na instalação.
2. Abrir a grelha de quartos em `/rooms`. T01 tem um lugar e T02 tem dois.
3. Tentar criar uma reserva sobreposta em T01. Verificar que a aplicação
   indica a indisponibilidade. Experimentar uma reserva com início no dia em
   que o grupo anterior sai.
4. Abrir `/report/meal_summary/PILOT-1`. O Grupo de teste 1 chega na sexta-feira
   às 18h e sai no domingo às 11h. Deve aparecer no jantar de sexta-feira e no
   pequeno-almoço de domingo. Os horários de serviço são configuráveis em
   `config/polanco.php`.

## Cópias de segurança

A verificação automática exporta a base MySQL do piloto e restaura-a numa
segunda base de teste. Antes de usar o sistema na Casa, definir quem recebe
os avisos de falha, onde guardar a cópia e com que frequência ensaiar o
restauro.

## Acesso na Casa

O Compose está ligado ao endereço local do computador por defeito. Para
experimentar a partir de outros computadores, ligar um proxy HTTPS no mesmo
servidor, restringir o acesso à rede da equipa e definir `PILOT_APP_URL` com
o endereço HTTPS. Configurar o Laravel para confiar nos cabeçalhos encaminhados
pelo endereço do proxy e ativar cookies seguros. Os dados da demonstração e
as suas credenciais destinam-se
ao ambiente de teste.

No Render, o Dockerfile desta aplicação é `./Dockerfile` e o contexto é a
raiz do repositório. Uma eventual instalação nesse serviço precisa da sua
própria base MySQL persistente e de variáveis de ambiente próprias.
