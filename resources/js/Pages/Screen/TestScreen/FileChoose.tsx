import DupLayout from "@/Layouts/BuildLayout/DupLayout";
import { useForm } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

const FileChoose = () => {

	const { data, setData, post, progress } = useForm({
		name: null,
		upload_file: null,
	})

	function submit(e) {
		e.preventDefault()
		post('/test/uploadfile')
	}

	return (
		<div className="bg-white grid grid-cols-12 justify-center items-center p-4">
			<div className="col-span-0 lg:col-span-3">
				<img src="http://acar11x.dev/storage/test/uploads/RqNKFxNJMNjl7NrbGgVgsM92DU30n5iFPyAAZV8d.png" alt="php logo" className="w-32 h-32 mx-auto" />
			</div>
			<div className="col-span-12 lg:col-span-6 bg-red-100 p-4">
				<form onSubmit={submit} className="flex flex-col space-y-2">
					<input type="text" value={data.name} onChange={e => setData('name', e.target.value)} />

					<input type="file" onChange={e => setData('upload_file', e.target.files[0])} />
					{progress && (
						<progress value={progress.percentage} max="100">
							{progress.percentage}%
						</progress>
					)}
					<Button variant="outlined" className="hover:border-blue-500 hover:text-green-500" type="submit">Submit form</Button>

				</form>
			</div>
		</div>
	);
}

FileChoose.layout = (page: React.ReactNode) => <DupLayout>
	{page}
</DupLayout>

export default FileChoose;