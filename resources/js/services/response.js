const SUCCESS = 200
const NOCONTENT = 204
const NOTMODIFIED = 304
const BADREQUEST = 400
const UNAUTHORISED = 401
const FORBIDDEN = 403
const NOTFOUND = 404
const CONFLICT = 409
const UNKNOWN = 500

export default class Response {

  constructor (statusCode, data) {
    this.statusCode = statusCode
    this.data = data
  }

  get isError () {
    return this.statusCode >= 400
  }

  get wasSuccess () {
    return this.statusCode >= 200 && this.statusCode < 400
  }

  get response () {
    return this.data
  }

  get hasContent() {
    return this.data !== null
  }

  get noContent() {
    return this.wasSuccess && this.statusCode === NOCONTENT
  }

  get notModified() {
    return this.wasSuccess && this.statusCode === NOTMODIFIED
  }

  get badRequest() {
    return this.isError && this.statusCode === BADREQUEST
  }

  get unauthorised() {
    return this.isError && this.statusCode === UNAUTHORISED
  }

  get forbidden() {
    return this.isError && this.statusCode === FORBIDDEN
  }

  get notFound() {
    return this.isError && this.statusCode === NOTFOUND
  }

  get conflict() {
    return this.isError && this.statusCode === CONFLICT
  }

  get unknown() {
    return this.isError && this.statusCode === UNKNOWN
  }
}