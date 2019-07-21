import Uuid   from './Uuid';
import moment from 'moment';

class User {
    constructor(uuid, username, lastActivity, current = false, typing = false) {
        this.setUuid(uuid);
        this.username     = username;
        this.lastActivity = moment.unix(lastActivity);
        this.current      = current;
        this.typing       = typing;
        this.permissions  = [];
        this.online       = false;
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

    get isTyping() {
        return this.typing;
    }

    get isOnline() {
        return this.online;
    }

    addPermissions(channel, permissions) {
        this.permissions[channel] = permissions;
    }

    can(channel, permission) {
        return this.permissions[channel] & permission;
    }
}

export default (uuid, username, lastActivity, current = false, typing = false) => new User(uuid, username, lastActivity, current, typing);