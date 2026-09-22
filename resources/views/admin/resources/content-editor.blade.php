<div id="content-editor" class="full"></div>
<script>
(() => {
    const source = document.querySelector('textarea[name="content"]');
    const target = document.getElementById('content-editor');
    const kind = document.querySelector('[name="kind"]');
    if (!source || !target) return;
    const labels = {title:'العنوان',description:'الوصف',content:'نص المقال',price:'السعر أو وصف الباقة',features:'مميزات الخدمة (سطر لكل ميزة)',required_documents:'المستندات المطلوبة (سطر لكل مستند)',requires_choices:'يشترط اختيار رغبة',answer:'الإجابة',brand:'اسم الموقع',tagline:'وصف الهوية',footer:'وصف التذييل',academic_year:'السنة الأكاديمية',navigation:'روابط التنقل',eyebrow:'النص أعلى العنوان',hero_image:'رابط صورة الرئيسية',benefits:'مميزات المنصة',steps:'خطوات التقديم',services_title:'عنوان الخدمات',benefits_title:'عنوان المميزات',steps_title:'عنوان الخطوات',faq_title:'عنوان الأسئلة',articles_title:'عنوان المقالات',category:'التصنيف',excerpt:'ملخص المقال',date:'تاريخ المقال',readTime:'وقت القراءة',image:'رابط الصورة'};
    const defaults = {
        service:{description:'',price:'',features:[],required_documents:[],requires_choices:false},
        faq:{answer:''},
        article:{category:'',excerpt:'',content:'',date:'',readTime:'',image:''},
        page:{title:'',description:''},
        settings:{brand:'',tagline:'',footer:'',academic_year:'',required_documents:[],navigation:[]}
    };
    let data;
    try {data=JSON.parse(source.value || '{}');} catch {return;}
    if(!data || Array.isArray(data)) data={};
    const sync=()=>{source.value=JSON.stringify(data,null,2);};
    function render() {
        target.replaceChildren();
        Object.entries(data).forEach(([key,value])=>{
            const box=document.createElement('div');
            const label=document.createElement('label');label.textContent=labels[key]||key;box.append(label);
            let input;
            if(typeof value==='boolean'){input=document.createElement('input');input.type='checkbox';input.checked=value;input.style.width='auto';}
            else {input=document.createElement('textarea');input.rows=Array.isArray(value)?4:3;input.value=Array.isArray(value)?(value.every(v=>typeof v==='string')?value.join('\n'):JSON.stringify(value,null,2)):String(value??'');}
            label.append(input);
            input.addEventListener('input',()=>{
                if(typeof value==='boolean')data[key]=input.checked;
                else if(Array.isArray(value)){
                    if(['navigation','benefits'].includes(key)){try{data[key]=JSON.parse(input.value);input.setCustomValidity('');}catch{input.setCustomValidity('استخدم قائمة JSON صحيحة.');return;}}
                    else data[key]=input.value.split('\n').map(v=>v.trim()).filter(Boolean);
                } else data[key]=input.value;
                sync();
            });
            if(['navigation','benefits'].includes(key)){const hint=document.createElement('small');hint.textContent='قائمة أزواج: [["العنوان", "الوصف أو الرابط"]]';box.append(hint);}
            target.append(box);
        });
        sync();
    }
    if(Object.keys(data).length===0)data={...(defaults[kind.value]||defaults.page)};
    render();
    source.parentElement.style.display='none';
    kind?.addEventListener('change',()=>{data={...(defaults[kind.value]||defaults.page),...data};render();});
})();
</script>
