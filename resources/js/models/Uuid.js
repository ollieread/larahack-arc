class Uuid {
    constructor(uuid) {
        this.uuid = uuid;
    }

    toString() {
        return this.uuid;
    }

    is(other) {
        if (other instanceof Uuid) {
            return this.uuid === other.uuid;
        }

        return this.uuid === other;
    }
}

export default (uuid) => new Uuid(uuid);