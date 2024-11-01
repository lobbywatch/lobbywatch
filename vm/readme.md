# Lobbywatch VM Setup

## CoreOS Bootstrap

Fedora CoreOS lacks python

```shell
ssh core@ip
sudo rpm-ostree install python3
sudo systemctl reboot
````

## Run ansible

```shell
ansible-playbook -i inventory.yml setup.yml 
````

## Resources

- https://www.freedesktop.org/software/systemd/man/latest/systemd.timer.html
- https://docs.podman.io/en/latest/markdown/podman-systemd.unit.5.html
- https://docs.ansible.com/ansible/latest/getting_started/index.html
- https://docs.ansible.com/ansible/latest/collections/ansible/builtin/index.html
