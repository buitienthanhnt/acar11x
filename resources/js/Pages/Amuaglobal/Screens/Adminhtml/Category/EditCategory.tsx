import { PageWithLayout } from "@/Pages/Amuaglobal/types/PageWithLayout"
import BaseLayout from "../Layout/BaseLayout"
import { CategoryDetailType } from "@/Pages/Amuaglobal/types/Category"

const EditCategory: PageWithLayout<{
  category: CategoryDetailType
}> = ({ category }) => {

  return (
    <div>
      edit category
      {category.name}
    </div>
  )
}

EditCategory.layout = page => <BaseLayout children={page} title="edit category"></BaseLayout>
export default EditCategory;