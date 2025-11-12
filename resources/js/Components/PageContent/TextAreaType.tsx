import Dropdown from "../Dropdown";
import PrimaryButton from "../PrimaryButton";

const TextAreaType = (props) => {

	return (
		<Dropdown>
			<Dropdown.Trigger>
				<PrimaryButton><p>title of dropdown content</p></PrimaryButton>
			</Dropdown.Trigger>
			<Dropdown.Content contentClasses={'px-2 py-1 bg-white'} width="w-60" align={'left'}>
				<p>{props.content.value}</p>
			</Dropdown.Content>
		</Dropdown>
	)
}

export default TextAreaType;