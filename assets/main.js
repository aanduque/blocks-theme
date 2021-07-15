
import Vue from 'vue/dist/vue.js';
import moment from 'moment';
import underscore from 'underscore';

const bucket = 'JuZPF4WemRttpJ7eKi15kH';

Vue.prototype._ = underscore;
Vue.prototype.moment = moment;

window.blocks = new Vue({
	el: "#main",
	watch: {
		form: {
			deep: true,
			handler(new_value, old_value) {

				if (new_value === '') {

					return;

				}

				this.$nextTick(() => {

					this.open(this.form.block);

					console.log(this.form.block);

					this.focus_form();

				});
			}
		},
	},
	methods: {

		is_open(id) {
			return this.opened.includes(id);
		},
		open(id) {
			this.opened.push(id);
		},
		close(id) {
			this.opened = this.opened.filter((value) => value != id)
		},
		focus_form() {
			setTimeout(() => {

				console.log('tried to focus')
				document.getElementById("task").focus();
				document.getElementById("add_new").scrollIntoView({
					behavior: 'smooth',
				});

			}, 400);
		},
		open_form(block) {
			if (this.form === '') {

				this.form = {
					id: Date.now(),
					done: false,
					order: this.todos.length + 1,
					name: '',
					day: this.day,
					description: '',
					block: block,
				};

			} else {

				this.form.block = block;

			}
		},
		submit_form() {
			this.todos.push(this.form);
			this.save();
			this.send_to_api();
			this.close_form();
		},
		close_form() {
			this.form = '';
		},
		done(id) {
			const todo = this._.findWhere(this.todos, { id: id });
			todo.done = !todo.done;
		},
		remove_todo(id) {
			this.todos = this.todos.filter((todo) => todo.id != id)
		},
		edit_todo(id) {

			const todo = this._.findWhere(this.todos, { id: id });

			this.remove_todo(id);

			this.form = todo;

		},
		load_from_form() {

			this.parse_share(this.data_to_load);

			this.data_to_load = '';
			this.load_form = false;

			this.load();

		},
		parse_share(hash) {

			if (!hash) {

				const hash = window.location.hash;

			}

			if (hash) {

				console.log(hash);

				const value = atob(hash.substring(1));

				const decoded = JSON.parse(value);

				console.log('Got from hash', decoded);

				if (decoded) {

					this.save(decoded);

					window.location.hash = '';

				}

			} // end if;

		},
		share() {

			window.location.hash = btoa(unescape(encodeURIComponent((JSON.stringify(this.$data)))));

		},
		load() {

			const list = [
				'opened',
				'todos',
				'show_controls',
				'show_descriptions',
				'show_completed',
				'show_timeline',
				// 'blocks',
			];

			list.forEach((index) => {

				const value = localStorage.getItem(index);

				console.log('Got value', value);

				if (value !== null) {

					this[index] = JSON.parse(value);

				} // end if;

			})

		},
		save(source) {

			const list = [
				'opened',
				'todos',
				'blocks',
				'show_controls',
				'show_descriptions',
				'show_completed',
				'show_timeline',
			];

			if (!source) {

				source = this;

			} // end if;

			const saved = {};

			list.forEach((index) => {

				saved[index] = source[index];

				const value = localStorage.setItem(index, JSON.stringify(source[index]));

			});

		},
		get_block(block_id) {

			return this._.findWhere(this.blocks, { id: block_id });

		},
		get_setting(block) {

			let settings = this._.findWhere(this.processed_settings, { block: block });

			if (!settings) {

				const defaults = {
					alpha: {
						block: 'alpha',
						start: '07:00',
						end: '09:50',
						day: this.day,
					},
					beta: {
						block: 'alpha',
						start: '10:00',
						end: '12:50',
						day: this.day,
					},
					lunch: {
						block: 'lunch',
						start: '13:00',
						end: '14:50',
						day: this.day,
					},
					gama: {
						block: 'gama',
						start: '15:00',
						end: '16:50',
						day: this.day,
					},
					snacks: {
						block: 'snacks',
						start: '17:00',
						end: '17:20',
						day: this.day,
					},
					delta: {
						block: 'delta',
						start: '17:30',
						end: '18:30',
						day: this.day,
					},
					night: {
						block: 'night',
						start: '18:30',
						end: false,
						day: this.day,
					},
				}

				settings = defaults[block];

			}

			return settings;

		},
		should_display_now(block) {

			const settings = this.get_setting(block);

			const now = this.moment();
			const start = this.moment(settings.start, 'HH:mm');
			const end = settings.end ? this.moment(settings.end, 'HH:mm') : this.moment('23:59', 'HH:mm');

			const should_display_now = now.isAfter(start) && now.isBefore(end);

			if (this.get_block(block).type === 'separator' && should_display_now) {

				console.log('Block...')

				this.waiting_block = this.get_block(block);

			} // end if;

			return should_display_now;

		},
		close_all() {

			this.form = '';
			this.load_form = false;

		},
		should_wait() {

			// console.log(this.ignore_waiting.includes(this.waiting_block.id));

			return this.waiting_block && !this.ignore_waiting.includes(this.waiting_block.id);

		},
		send_to_api() {

			const saved = this.$data;

			fetch('https://kvdb.io/' + bucket + '/' + 'arindo', {
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				method: 'POST',
				body: JSON.stringify(saved),
			}).then((results) => console.log(results))

		},
		load_from_api() {

			fetch('https://kvdb.io/' + bucket + '/' + 'arindo', {
				headers: {
					'Accept': 'application/json',
					'Content-Type': 'application/json'
				},
				method: 'GET',
			}).then(response => response.json())
				.then(data => {

					console.log(typeof data);

					console.log(data);

					const list = [
						'opened',
						'todos',
						'show_controls',
						'show_descriptions',
						'show_completed',
						'show_timeline',
						// 'blocks',
					];

					list.forEach((index) => {

						console.log('Got value from api', data[index]);

						this[index] = data[index];

					})

				});

		}
	},
	computed: {
		processed_todos() {

			return this._.groupBy(this._.filter(this._.sortBy(this.todos, (todo) => -todo.order), (todo) => todo.day === this.day), 'block');

		},
		processed_settings() {

			return this._.filter(this.settings, (setting) => setting.day === this.day)

		},
	},
	updated() {
		this.$nextTick(function () {
			this.send_to_api();
		});
	},
	mounted() {

		this.day = this.moment().format('YYYYMMDD');

		// this.parse_share();

		this.load_from_api();

		// window.onbeforeunload = (event) => this.save();
		window.onbeforeunload = (event) => this.send_to_api();

		setInterval(() => {

			console.log('Saving...');

			this.save();

		}, 30000);

	},
	data() {
		return {
			menu_open: false,
			ignore_waiting: [],
			waiting_block: false,
			load_form: false,
			data_to_load: '',
			day: false,
			show_timeline: true,
			show_completed: true,
			show_date_selector: false,
			show_descriptions: true,
			show_controls: false,
			form: '',
			opened: ['alpha'],
			todos: [],
			all_settings: [
				{
					block: 'alpha',
					start: '07:00',
					end: '12:00',
					day: '20210715',
				}
			],
			blocks: [
				{
					id: 'alpha',
					type: 'block',
					name: "Alpha α",
					classes: 'border-red-300',
					start: '',
					end: '',
					description: '',
					priority: 0,
				},
				{
					id: 'beta',
					type: 'block',
					name: "Beta β",
					classes: 'border-yellow-300',
					start: '',
					end: '',
					description: '',
					priority: 0,
				},
				{
					id: 'lunch',
					type: 'separator',
					name: "Lunch",
					classes: '',
					start: '',
					end: '',
					description: '',
					priority: 0,
				},
				{
					id: 'gama',
					type: 'block',
					name: "Gama 𝛾",
					classes: 'border-blue-300',
					start: '',
					end: '',
					description: '',
					priority: 0,
				},
				{
					id: 'snacks',
					type: 'separator',
					name: "Snacks",
					classes: 'bg-gray-50',
					start: '',
					end: '',
					description: '',
					priority: 0,
				},
				{
					id: 'delta',
					type: 'block',
					name: "Delta δ",
					classes: 'border-green-300',
					start: '',
					end: '',
					description: '',
					priority: 0,
				},
				{
					id: 'night',
					type: 'separator',
					name: "End of Work",
					classes: 'bg-gray-50',
					start: '',
					end: '',
					description: '',
					priority: 0,
				},
			]
		}
	}
});