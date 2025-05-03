import tkinter as tk

   root = tk.Tk()
   root.title("تحديث أسعار الذهب")

   # إنشاء حقول الإدخال
   tk.Label(root, text="سعر الذهب عيار 21:").pack()
   entry_21 = tk.Entry(root)
   entry_21.pack()

   tk.Label(root, text="سعر الذهب عيار 18:").pack()
   entry_18 = tk.Entry(root)
   entry_18.pack()

   def save_prices():
       price_21 = entry_21.get()
       price_18 = entry_18.get()
       print(f"تم تحديث الأسعار: عيار 21 = {price_21}, عيار 18 = {price_18}")

   tk.Button(root, text="حفظ", command=save_prices).pack()

   root.mainloop()