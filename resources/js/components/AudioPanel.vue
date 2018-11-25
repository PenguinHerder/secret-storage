<template>
	<div>
		<div v-if="message" class="row">
			<div class="col-md-12">
				<div :class="messageType">{{ message }}</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<select v-if="!showAnalysis && audio.analyses.length > 0" v-model="selectedAnalysisId" class="form-control">
					<option v-for="analysis in audio.analyses" :value="analysis.id">By {{ analysis.author.name }}</option>
				</select>
			</div>
			<div class="col-md-6">
				<button @click="showAnalysis = !showAnalysis" class="btn btn-primary float-right">{{ showAnalysis ? "Hide" : (hasOwnAnalysis ? "Edit your analysis" : "Create new content analysis") }}</button>
			</div>
		</div>
		<hr>
		<div v-if="!showAnalysis">
			<table class="table table-sm">
				<thead>
					<tr>
						<th>Start</th>
						<th>Content</th>
						<th>Jump</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="section in selectedAnalysis">
						<td>{{ secondsToTime(section.start) }}</td>
						<td>{{ section.noise ? "[Noise]" : section.content }}</td>
						<td>
							<button class="btn btn-sm btn-info">
								<i class="fa fa-play"></i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div v-if="showAnalysis" class="analysis-table">
			<table class="table table-sm">
				<thead>
					<tr>
						<th>Start</th>
						<th>End</th>
						<th>Content</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="section in analysis">
						<td>{{ secondsToTime(section.start) }}</td>
						<td>{{ secondsToTime(section.end) }}</td>
						<td>{{ section.noise ? "[Noise]" : section.content }}</td>
						<td></td>
					</tr>
					<tr v-if="error">
						<td colspan="4">
							<div class="alert alert-danger">{{ error }}</div>
						</td>
					</tr>
					<tr>
						<td>{{ secondsToTime(section.start) }}</td>
						<td>
							<input type="text" v-model="sectionEndModel" class="form-control"></br>
							<button @click="currentTimeClick" class="btn btn-sm btn-secondary">Current time</button>
							<button @click="endTimeClick" class="btn btn-sm btn-secondary">End</button>
						</td>
						<td>
							<input type="text" v-model="section.content" class="form-control"><br>
							<label>
								<input type="checkbox" v-model="section.noise"> Noise
							</label>
						</td>
						<td>
							<button @click="addSectionClick" class="btn btn-primary float-right">Add</button>
						</td>
					</tr>
					<tr>
						<td colspan="3"></td>
						<td>
							<button @click="saveAnalysisClick" class="btn btn-primary">Save Analysis</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<audio controls :src="audioUri" ref="audio">
			Your browser does not support the
			<code>audio</code> element.
		</audio>
    </div>
</template>

<script>
    export default {
		props: {
			audio: Object,
			userId: Number,
			audioUri: String,
			saveAnalysisUri: String,
		},

		data() {
			return {
				showAnalysis: false,
				analysis: [],
				section: { start: 0, end: null, noise: false, content: "" },
				sectionEndModel: '',
				error: null,
				message: null,
				messageType: ['alert', 'alert-success'],
				selectedAnalysisId: null,
				hasOwnAnalysis: false,
			}
		},

        mounted() {
            console.log(this.audio)

			if(this.audio.analyses.length > 0) {
				this.selectedAnalysis = this.audio.analyses[0].id
			}

			for(let i in this.audio.analyses) {
				if(this.audio.analyses[i].user_id === this.userId) {
					this.hasOwnAnalysis = true
					this.analysis = this.audio.analyses[i].sections
					this.selectedAnalysisId = this.audio.analyses[i].id
				}
			}
        },

		computed: {
			selectedAnalysis: function() {
				for(let i in this.audio.analyses) {
					if(this.audio.analyses[i].id === this.selectedAnalysisId) {
						return this.audio.analyses[i].sections
					}
				}

				return []
			}
		},

		methods: {
			saveAnalysisClick() {
				if(this.analysis.length < 1) {
					this.error = "Analysis is too short"
				}
				else {
					this.saveAnalysis()
				}
			},

			saveAnalysis() {
				axios.post(this.saveAnalysisUri, {
					data: JSON.stringify(this.analysis)
				}).then(response => {
					this.audio.analyses = response.data.analyses
					this.displayMessage("Your analysis has been saved for the review, Thank you! :)", 'success')
				}).catch(error => {
					this.displayMessage(error.response.data.message, 'danger')
				})
			},

			displayMessage(message, type) {
				this.messageType = ['alert', 'alert-' + type]
				this.message = message
				this.showAnalysis = false
				setTimeout(() => { this.message = null }, 4000)
			},

			currentTimeClick() {
				this.section.end = Math.floor(this.$refs.audio.currentTime)
				this.sectionEndModel = this.secondsToTime(this.section.end)
			},

			endTimeClick() {
				this.section.end = Math.floor(this.$refs.audio.duration)
				this.sectionEndModel = this.secondsToTime(this.section.end)
			},

			addSectionClick() {
				if(this.validate()) {
					this.section.end = this.humanToSeconds(this.sectionEndModel)
					const newSection = JSON.parse(JSON.stringify(this.section))
					this.analysis.push(newSection)
					this.section.start = newSection.end
					this.section.end = null
					this.section.noise = false
					this.section.content = ""
					this.sectionEndModel = ""
					this.error = null
				}
			},

			validate() {
				if(! /^\d{2,3}:[0-5]\d$/.test(this.sectionEndModel)) {
					this.error = "invalid end time"
					return false
				}

				if(this.humanToSeconds(this.sectionEndModel) <= this.section.start) {
					this.error = "invalid end time"
					return false
				}

				if(this.section.noise == false && this.section.content == '') {
					this.error = "missing content description"
					return false
				}

				return true
			},

			secondsToTime(seconds) {
				if(seconds === null) {
					return ''
				}

				const minutes = Math.floor(seconds / 60).toString()
				const secs = (seconds % 60).toString()
				
				return minutes.padStart(2, '0') + ":" + secs.padStart(2, '0')
			},

			humanToSeconds(time) {
				const split = time.split(':')
				return parseInt(split[0]) * 60 + parseInt(split[1])
			}
		}
    }
</script>
