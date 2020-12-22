export default class Helping {
    static getConfig() {
        let token = localStorage.token

        let config = {
            headers: {
                'Content-Type': 'application/json',
                'Authorization': token,
                // 'use_cognito_pool': false
            }
        }

        return config
    }

    static removeItem(items, id) {
        return items.filter(function (item) {
            return item.id !== id
        })
    }

    static getBase64(file, cb) {
        let reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function () {
            cb(reader.result)
            return reader.result
        };
        reader.onerror = function (error) {
            console.log('Error: ', error);
        };
    }

    static isEmpty(obj) {
        for(var key in obj) {
            if(obj.hasOwnProperty(key))
                return false;
        }
        return true;
    }

    static isAuth($this, response) {
        if(response.status === 401 && response.message === "Connection terminated unexpectedly") {
            window.location.reload();
        }
        if(response.status === 401) {
            delete localStorage.token
            $this.props.history.push("/")
        }
    }
}