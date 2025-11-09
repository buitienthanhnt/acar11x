import TopPageLayout from "@/Layouts/BuildLayout/TopPageLayout";
import List from "./List";

const PageByTag = (props) => {
	const { pages } = props;

	return (
		<TopPageLayout>
			<head>
				<title>tag: {props.tag}</title>
			</head>
			<div className="space-y-1">
				<h2 className="bg-white rounded-md p-2 font-semibold text-xl text-blue-600">Tìm theo tag: {props.tag}</h2>
				<List paginate={pages}></List>
			</div>
		</TopPageLayout>
	)
}

export default PageByTag;