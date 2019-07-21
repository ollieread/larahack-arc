import Uuid   from './Uuid';
import moment from 'moment';

class Message {
    constructor(uuid, type, body, postedAt, user, action = null, metadata = [], mentions = []) {
        this.setUuid(uuid);
        this.type     = type;
        this.body     = body;
        this.postedAt = moment.unix(postedAt);
        this.user     = user;
        this.action   = action;
        this.metadata = metadata;
        this.mentions = mentions;
    }

    setUuid(value) {
        if (!(value instanceof Uuid)) {
            value = new Uuid(value);
        }

        this.uuid = value;
    }

    isText() {
        return this.type & 0x00000001;
    }

    isAction() {
        return this.type & 0x00000020;
    }

    get message() {
        return this.body;
    }
}

export default (uuid, type, message, postedAt, user, action = null, metadata = [], mentions = []) => new Message(uuid, type, message, postedAt, user, action, metadata, mentions);