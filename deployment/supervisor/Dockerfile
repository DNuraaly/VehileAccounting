FROM identifize-backend
LABEL authors="Doolatbek A."

# Install Supervisor
RUN apt-get update \
&& apt-get install -y supervisor \
&& mkdir -p /var/log/supervisor

# Copy Supervisor configuration
COPY supervisord.conf /etc/supervisor/supervisord.conf
COPY supervisord.d/ /etc/supervisor/conf.d/
COPY startsupervisor.sh /etc/supervisor/startsupervisor.sh
RUN chmod +x /etc/supervisor/startsupervisor.sh

ARG ENABLE_SUPERVISOR
ENV RUN_SUPERVISOR=$ENABLE_SUPERVISOR

CMD ["/etc/supervisor/startsupervisor.sh"]
