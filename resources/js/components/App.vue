<template>
	<div class="d-flex min-vh-100">
		<FileList
			:pdfs="pdfs"
			@pdfUrl="setCurrentFile"
			@newPdfAdded="showSuccessAlert"
		></FileList>
		<div class="w-75 border bg-white">
			<div class="bg-primary">
				<div class="p-3 fw-bolder text-white" style="font-size: 18px">
					{{ filename }}
				</div>
			</div>
			<div
				class="alert alert-success alert-dismissible fade show m-2"
				role="alert"
				v-if="successAlert"
			>
				Pdf uploaded successfully.
				<button
					type="button"
					class="btn-close"
					data-bs-dismiss="alert"
					aria-label="Close"
				></button>
			</div>
			<div class="m-3">
				<iframe
					:src="currentFilepath"
					width="100%"
					v-if="sourceNotEmpty"
					class="min-vh-100"
				>
				</iframe>
			</div>
		</div>
	</div>
</template>

<script>
import FileList from "./FileList.vue";
export default {
	data() {
		return {
			errors: [],
			successAlert: false,
			pdfs: [],
			currentFilename: "",
			currentFilepath: "",
		};
	},
	components: {
		FileList,
	},
	methods: {
		showSuccessAlert() {
			this.successAlert = true;
			window.setTimeout(() => {
				this.successAlert = false;
			}, 4000);
			this.getPdfs();
		},
		getPdfs() {
			axios
				.get("/pdf-list")
				.then((response) => {
					this.pdfs = response.data.pdfs;
				})
				.catch((error) => {
					console.log(error.response.data.errors);
				});
		},
		setCurrentFile(data) {
			this.currentFilename = data.filename;
			this.currentFilepath = data.filepath + "#toolbar=0";
		},
	},
	computed: {
		sourceNotEmpty() {
			return this.currentFilepath !== "";
		},
		filename() {
			if (this.currentFilename !== "") {
				return this.currentFilename;
			}
			return "No File Selected";
		},
	},
	mounted() {
		this.getPdfs();
	},
};
</script>
<style scoped>
</style>
