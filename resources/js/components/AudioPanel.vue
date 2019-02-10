<template>
	<div class="audio-content-analysis">
		<h4>Audio Content Analysis</h4>
		<div v-if="message" class="row">
			<div class="col-md-12">
				<div :class="messageType">{{ message }}</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div v-if="!showAnalysis && audio.analyses.length > 0">
					<select v-if="audio.analyses.length > 1" v-model="selectedAnalysisId" class="form-control">
						<option v-for="analysis in audio.analyses" :value="analysis.id">By {{ analysis.author.name }}</option>
					</select>
					<div v-else>
						<span>By {{ audio.analyses[0].author.name }}</span>
					</div>
				</div>
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
						<th>Length</th>
						<th>Content</th>
						<th>Jump</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="section in selectedAnalysis.sections">
						<td>{{ secondsToTime(section.start) }}</td>
						<td>{{ secondsToTime(section.end - section.start) }}</td>
						<td>{{ section.content }}</td>
						<td>
							<button @click="setPositionClick(section.start)" class="btn btn-sm btn-info">
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
					<tr v-for="(section, index) in analysis">
						<td>{{ secondsToTime(section.start) }}</td>
						<td>{{ secondsToTime(section.end) }}</td>
						<td>{{ section.content }}</td>
						<td>
							<button v-if="index === (analysis.length- 1)" @click="removeLastItemClick" class="btn btn-sm btn-link">
								<i class="fa fa-times"></i>
							</button>
						</td>
					</tr>
					<tr v-if="error">
						<td colspan="4">
							<div class="alert alert-danger">{{ error }}</div>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" v-model="sectionStartModel" class="form-control"></br>
							<button @click="currentTimeStartClick" class="btn btn-sm btn-secondary">Set start time</button>
						</td>
						<td>
							<input type="text" v-model="sectionEndModel" class="form-control"></br>
							<button @click="currentTimeEndClick" class="btn btn-sm btn-secondary">Set end time</button>
						</td>
						<td>
							<input type="text" v-model="section.content" class="form-control">
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
			approveAnalysisUri: String,
			canApprove: Boolean,
		},

		data() {
			return {
				showAnalysis: false,
				analysis: [],
				section: { start: 0, end: null, content: "" },
				sectionStartModel: '',
				sectionEndModel: '',
				error: null,
				message: null,
				messageType: ['alert', 'alert-success'],
				selectedAnalysisId: null,
				hasOwnAnalysis: false,
			}
		},

        mounted() {
			if(this.audio.analyses.length > 0) {
				this.selectedAnalysisId = this.audio.analyses[0].id
			}

			for(let i in this.audio.analyses) {
				if(this.audio.analyses[i].user_id === this.userId) {
					this.hasOwnAnalysis = true
					this.analysis = this.audio.analyses[i].sections
					this.section.start = this.analysis[this.analysis.length - 1].end
					this.selectedAnalysisId = this.audio.analyses[i].id
				}
			}
        },

		computed: {
			selectedAnalysis: function() {
				for(let i in this.audio.analyses) {
					if(this.audio.analyses[i].id === this.selectedAnalysisId) {
						return this.audio.analyses[i]
					}
				}

				return []
			}
		},

		methods: {
			removeLastItemClick() {
				if(this.analysis.length > 0) {
					this.analysis.pop()
					this.section.start = this.analysis.length ? this.analysis[this.analysis.length - 1].end : 0
				}
			},

			approveAnalysisClick() {
				axios.post(this.approveAnalysisUri, {
					id: this.selectedAnalysisId
				}).then(response => {
					this.audio.analyses = response.data.analyses
					this.displayMessage("This analysis is now visible to everyone, Thank you! :)", 'success')

					// silly round to trigger recomputation of selectedAnalysis
					const tmp = this.selectedAnalysisId
					this.selectedAnalysisId = null
					this.selectedAnalysisId = tmp 
				}).catch(error => {
					this.displayMessage(error.response.data.message, 'danger')
				})
			},

			setPositionClick(position) {
				this.$refs.audio.currentTime = position
				this.$refs.audio.play()
			},

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

			currentTimeStartClick() {
				this.section.start = Math.floor(this.$refs.audio.currentTime)
				this.sectionStartModel = this.secondsToTime(this.section.start)
			},

			currentTimeEndClick() {
				this.section.end = Math.floor(this.$refs.audio.currentTime)
				this.sectionEndModel = this.secondsToTime(this.section.end)
			},

			addSectionClick() {
				if(this.validate()) {
					this.section.start = this.humanToSeconds(this.sectionStartModel)
					this.section.end = this.humanToSeconds(this.sectionEndModel)
					const newSection = JSON.parse(JSON.stringify(this.section))
					this.analysis.push(newSection)
					this.section.start = null
					this.section.end = null
					this.section.content = ""
					this.sectionStartModel = ""
					this.sectionEndModel = ""
					this.error = null
				}
			},

			validate() {
				if(! /^\d{2,3}:[0-5]\d$/.test(this.sectionStartModel)) {
					this.error = "invalid start time"
					return false
				}

				if(! /^\d{2,3}:[0-5]\d$/.test(this.sectionEndModel)) {
					this.error = "invalid end time"
					return false
				}

				if(this.humanToSeconds(this.sectionEndModel) <= this.humanToSeconds(this.sectionStartModel)) {
					this.error = "invalid end time"
					return false
				}

				if(this.section.content == '') {
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
