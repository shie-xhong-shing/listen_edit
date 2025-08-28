// app.js
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}
// app.js
new Vue({
    el: '#app',
    data: {
        data: {
            problem: '',
            origin: '',
            chinese_meaning: ''
        },
        id: null,
        error: '',
        saving: false,
        saveSuccess: false,
        saveTimeout: null
    },
    created() {
        this.fetchCurrentId();
        this.setupPolling();
    },
    watch: {
        data: {
            handler: function() {
                this.debouncedSaveData();
            },
            deep: true
        }
    },
    methods: {
        fetchCurrentId() {
            fetch('current_id.txt')
                .then(response => response.text())
                .then(id => {
                    if (id !== this.id) {
                        this.id = id;
                        this.fetchData();
                    }
                })
                .catch(() => {
                    this.error = 'Failed to fetch current ID.${error.message}';
                });
        },
        fetchData() {
            fetch(`get_data.php?id=${this.id}`)
                .then(response => response.json())
                .then(json => {
                    if (json.error) {
                        this.error = json.error;
                    } else {
                        this.data = json;
                    }
                })
                .catch(error => {
    this.error = `Failed to fetch current ID: ${error.message}`;
});

        },
        saveData() {
            this.saving = true;
            fetch('update_data.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: this.id, ...this.data })
            })
                .then(response => response.json())
                .then(json => {
                    this.saving = false;
                    if (json.error) {
                        this.error = json.error;
                    } else {
                        this.saveSuccess = true;
                        // Hide the success message after 2 seconds
                        setTimeout(() => {
                            this.saveSuccess = false;
                        }, 2000);
                    }
                })
                .catch(() => {
                    this.saving = false;
                    this.error = 'Failed to save data.';
                });
        },
        // Use the debounce function
        debouncedSaveData: debounce(function() {
            this.saveData();
        }, 1000), // Adjust the wait time as needed
        setupPolling() {
            setInterval(() => {
                this.fetchCurrentId();
            }, 2000); // Poll every 2 seconds
        }
    }
});

