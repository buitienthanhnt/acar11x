function TextEditorType(params) {
	return (
		<div className="grid grid-cols-1 md:grid-cols-5 xl:grid-cols-7 gap-1 ">
			<div className="col-span-1 md:col-span-4 p-1 xl:col-span-5 bg-white rounded-md">
				<p dangerouslySetInnerHTML={{ __html: params.content.value }}></p>
			</div>
			<div className="md:col-span-1 bg-blue-gray-100 rounded-md xl:col-span-2" />
		</div>
	)
}

export default TextEditorType