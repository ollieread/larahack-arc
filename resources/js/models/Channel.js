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
        this.messages    = [];
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
        this.onlineUsers = users.map(user => {
            user.online = true;
            return user.uuid.toString();
        });
        this.sortUsers();
    }

    setUsers(users) {
        this.users = users;
        this.sortUsers();
    }

    setEvents(events) {
        this.events = events;
    }

    isUserOnline(user) {
        return this.onlineUsers.includes(user.uuid.toString());
    }

    addMessage(message) {
        let firstMessage = this.messages[0];

        if (firstMessage && firstMessage.postedAt.isBefore(message.postedAt)) {
            let oldMessages = this.messages;
            this.messages   = [message].concat(oldMessages);
        } else {
            this.messages.push(message);
        }
    }

    addOnlineUser(user) {
        user.online = true;
        this.onlineUsers.push(user.uuid.toString());
        this.sortUsers();
    }

    removeOnlineUser(user) {
        user.online = false;
        window._.remove(this.onlineUsers, uuid => {
            return user.uuid.is(uuid);
        });
        this.sortUsers();
    }

    sortUsers() {
        let users       = this.users;
        let adminUsers  = window._.filter(users, user => user.can(this.uuid.toString(), 0x00000040));
        let onlineUsers = window._.filter(users, user => this.onlineUsers.includes(user.uuid.toString()) && !adminUsers.includes(user));
        let otherUsers  = window._.filter(users, user => !adminUsers.includes(user) && !onlineUsers.includes(user));
        this.users      = adminUsers.concat(onlineUsers.concat(otherUsers));
    }
}

export default (uuid, name, description, current = false, locked = false, archived = false) => new Channel(uuid, name, description, current, locked, archived);