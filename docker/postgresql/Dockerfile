FROM postgres:13

#RUN addgroup --gid 1000 app
#RUN adduser --disabled-password --gecos '' -uid 1000 -gid 1000 app

ENV PG_MAJOR 13
ENV POSTGIS_MAJOR 3
ENV POSTGIS_VERSION 3.0.2+dfsg-2.pgdg100+1


RUN apt-get update \
      && apt-cache showpkg postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR \
      && apt-get install -y --no-install-recommends \
           postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR=$POSTGIS_VERSION \
           postgresql-$PG_MAJOR-postgis-$POSTGIS_MAJOR-scripts=$POSTGIS_VERSION \
      && rm -rf /var/lib/apt/lists/*


COPY *.sql /docker-entrypoint-initdb.d/
