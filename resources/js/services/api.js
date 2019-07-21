import axios    from 'axios';
import store    from '../store';
import Response from './response';

class Api {
    /**
     * Construct the initial API object
     *
     * @param {string} name
     * @param {Array} params
     */
    constructor(name, params = []) {
        // Set the default headers
        this.headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        };

        // If a user is logged in, we want to set that header
        if (store.getters['Auth/isAuthed']) {
            this.headers.Authorization = 'Bearer ' + store.state.Auth.token;
        }

        // Grab the URI from the route definitions
        this.uri    = route(name, params);
        // Grab the method from the route definitions
        this.method = Ziggy.namedRoutes[name].methods[0].toLowerCase();
    }

    /**
     * Create an instance of Axios
     *
     * @returns {AxiosInstance}
     */
    get instance() {
        return axios.create({
            baseURL: process.env.MIX_API_BASE,
            withCredentials: false,
            headers: this.headers,
        });
    }

    /**
     * Set the `If-Modified-Since` header for the request
     *
     * @param {moment.Moment} moment
     * @returns {Api}
     */
    modified(moment) {
        if (moment) {
            this.headers['If-Modified-Since'] = moment.format('ddd, D MMM Y HH:mm:ss UTC');
        }

        return this;
    }

    /**
     * Set the `If-Unmodified-Since` header for the request
     *
     * @param {moment.Moment} moment
     * @returns {Api}
     */
    unmodified(moment) {
        if (moment) {
            this.headers['If-Unmodified-Since'] = moment.format('ddd, D MMM Y HH:mm:ss UTC');
        }

        return this;
    }

    /**
     * Make the request, optionally with the provided data
     *
     * @param {Object|null} data
     * @returns {Promise<Response|null>}
     */
    send(data = null) {
        if (data) {
            this.data = data;
        }

        let call;

        if (this.method === 'get') {
            call = () => this.instance.get(this.uri);
        }

        if (this.method === 'post') {
            call = () => this.instance.post(this.uri, this.data);
        }

        if (this.method === 'patch') {
            call = () => this.instance.patch(this.uri, this.data);
        }

        if (this.method === 'delete') {
            call = () => this.instance.delete(this.uri, this.data);
        }

        return new Promise((resolve, reject) => {
            call()
                .then(response => {
                    // If we have had an OKAY or NO-CONTENT status, everything went okay
                    if (response.status >= 200 && response.status < 400) {
                        // Because the OAuth2 implementation is a little bitch, it doesn't follow the
                        // same pattern as the other API responses
                        if (!response.data.data) {
                            return resolve(new Response(response.status, response.data));
                        }

                        // In the case of a 204, this will be empty. SHIT SON!
                        return resolve(new Response(response.status, response.data.data));
                    }
                })
                .catch(error => {
                    // There was an error response
                    if (error.response) {
                        // This is technically not an error
                        if (error.response.status === 304) {
                            resolve(new Response(error.response.status, error.response.data.data));
                        }

                        // If there was an auth error
                        if (error.response.status === 401) {
                            // We want to tell the app the reauth and provide the handler
                            store.dispatch('Auth/requiresReauth', this.reauthHandler);
                            // We resolve with an empty, because we don't want the underlying code to assume
                            // there was an error, as we will replay it using the handler
                            resolve(new Response(error.response.status, error.response.data.data));
                        }

                        // If there was a validation error
                        if (error.response.status === 422) {
                            // We return the response because that's going to back whacked into
                            // vee validator, unless there's a way for us to do that here...
                            reject(new Response(error.response.status, error.response.data.errors));
                        }

                        reject(new Response(error.response.status, error.response.data));
                    }

                    reject(new Response(500, null));
                });
        });
    }
}

// Does this need explaining?
export default (name, params = []) => new Api(name, params)