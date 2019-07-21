import Uuid from './Uuid';

class Channel {
    constructor(uuid, name, description, current = false, locked = false, archived = false) {
        this.setUuid(uuid);
        this.name        = name;
        this.description = description;
        this.current     = current;
        this.locked      = locked;
        this.archived    = archived;
        this.onlineUsers = [];
        this.users       = [];
    }

    setUuid(value) {
        if (!(value instanceof Uuid)) {
            value = new Uuid(value);
        }

        this.uuid = value;
    }

    get isCurrent() {
        return this.current;
    }

    get isLocked() {
        return this.locked;
    }

    get isArchived() {
        return this.archived;
    }

    setOnlineUsers(users) {
        this.onlineUsers = users;
        this.setUsers(this.users);
    }

    setUsers(users) {
        let adminUsers  = window._.filter(users, user => user.can(this.uuid.toString(), 0x00000040));
        let onlineUsers = window._.filter(users, user => this.onlineUsers.includes(user.uuid.toString()) && ! adminUsers.includes(user));
        let otherUsers  = window._.filter(users, user => !adminUsers.includes(user) && !onlineUsers.includes(user));
        this.users      = adminUsers.concat(onlineUsers.concat(otherUsers));
    }

    setEvents(events) {
        this.events = events;
    }

    isUserOnline(user) {
        return this.onlineUsers.includes(user.uuid.toString());
    }
}

export default (uuid, name, description, current = false, locked = false, archived = false) => new Channel(uuid, name, description, current, locked, archived);