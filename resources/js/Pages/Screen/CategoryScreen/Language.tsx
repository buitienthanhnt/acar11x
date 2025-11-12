import SingleLayout from "@/Layouts/BuildLayout/SingleLayout";
import { Form, useForm } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

const Language = (params) => {
	const { data, setData, post, progress } = useForm({
		name: '',
		avatar: null,
	});
	// console.log(progress);//  https://www.itsolutionstuff.com/post/laravel-react-js-file-upload-example-tutorialexample.html

	function submit(e) {
		e.preventDefault()
		post('/lang-setup')
	}

	/**
	 * Thành phần Form hoạt động bình thường như các thành phần inertiajs khác mà không bị tải lại trang
	 * có thể dùng Form trực tiếp như này hoặc dùng dưới dạng react form để kiểm soát trạng thái tốt hơn
	 * Cả 2 cách đều đựợc, nhưng cách này gọn hơn và giống html thuần hơn.
	 */
	return (
		<div className="flex justify-center items-center p-4 space-x-4">
			<Form action="/lang-setup" method="post" className="space-y-2"
				resetOnSuccess
				disableWhileProcessing
				showProgress={true}
			>
				<div>
					<input type="text" name="user.name" className="rounded-md" defaultValue="John Doe" />
				</div>
				<div>
					<select name="user.country" defaultValue="uk" className="rounded-md">
						<option value="us">United States</option>
						<option value="ca">Canada</option>
						<option value="uk">United Kingdom</option>
					</select>
				</div>
				<div>
					<input type="checkbox" name="user.subscribe" value="yes" defaultChecked />
				</div>
				<Button type="submit">Submit</Button>
			</Form>

			<form onSubmit={submit} className="space-y-2">
				<div>
					<input type="text" className="rounded-md" placeholder="user name" value={data.name} onChange={e => setData('name', e.target.value)} />
				</div>
				<div>
					<input type="file" multiple onChange={e => setData('avatar', e.target.files)} />
					{/* progress: thành phần hoạt động với upload file. */}
					<div>
						{progress && (
							<progress value={progress.percentage} max="100">
								{progress.percentage}%
							</progress>
						)}

						{progress && (
							<div className="w-full bg-gray-200 rounded-full dark:bg-gray-700">
								<div className="bg-blue-600 text-xs font-medium text-blue-100 text-center p-0.5 leading-none rounded-full" width={progress.percentage}> {progress.percentage}%</div>
							</div>
						)}
					</div>
					{/* <p>{progress && progress.percentage}%</p> */}
				</div>
				<Button type="submit">Submit</Button>
			</form>
		</div>
	)
}

/**
 * dùng khai báo: function Language(){} sẽ không dùng đựợc : .layout
 * mà phải khai báo: const Language = ()=>{} mới dùng .layout được
 */
Language.layout = page => <SingleLayout children={page} title="setup langs" />

export default Language;