import { PageWithLayout } from "@/Pages/Amuaglobal/types/PageWithLayout"
import BaseLayout from "../Layout/BaseLayout"
import { CategoryDetailType } from "@/Pages/Amuaglobal/types/Category"
import FormRender from "@/Pages/Amuaglobal/Components/FormRender"
import { FormFieldType } from "@/Pages/Amuaglobal/types/FormFieldType"
import { router, useForm } from "@inertiajs/react"
import { formartFormData } from "@/Pages/Amuaglobal/Helper/formHelper"
import { Button } from "@material-tailwind/react"

const CategoryEdit: PageWithLayout<{
  category: CategoryDetailType;
  formFields: FormFieldType[];
}> = ({ category, formFields }) => {

  let fields: Record<string, any> = {};

  const { data, setData, post, processing, errors, reset, recentlySuccessful } = useForm(formartFormData(formFields));

  return (
    <div className="space-y-4 p-4">
      <FormRender
        url={`/adminhtml/amua-category/update/${category.id}`}
        method="PUT"
        formFields={formFields}
        data={data}
        onChange={setData}
        errors={errors}
      />
      <Button onClick={() => {
        router.post(
          `/adminhtml/amua-category/update/${category.id}`,
          {
            ...data,
            _method: 'PUT', // thêm trường này vào body params để giả lập phương thức PUT 
            // vì trong laravel nếu dùng PUT thông thường server sẽ không nhận được body params khi gửi kèm file
            // bằng không phải đổi qua POST. Dùng như này thì trong router vẫn khai báo PUT bình thường để chuẩn hóa.
          },
          {
            headers: {
              'Content-Type': 'multipart/form-data' // Bắt buộc để server hiểu có file
            }
          }
        )
      }}
        children={'Cập nhật'}
        placeholder={undefined}
        onResize={undefined}
        onResizeCapture={undefined}
        onPointerEnterCapture={undefined}
        onPointerLeaveCapture={undefined}
        disabled={processing}
      >

      </Button>
    </div>
  )
}

CategoryEdit.layout = page => <BaseLayout children={page} title="edit category"></BaseLayout>
export default CategoryEdit;