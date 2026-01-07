from datetime import datetime, date
from calendar import monthrange

reason_list = (
    "annual service",
    "cosmetic repair",
    "mechanical repair - body",
    "mechanical repair - wing",
    "mechanical repair - engine"
)

outcome_list = (
    "return to service",
    "further action(s) required",
    "grounded - do not fly"
)


def get_job_date() -> date:
    # Just grabs today's date so we don’t have to type it in
    return datetime.now().date()


def get_previous_date() -> date:
    # Keep asking until the user types a real date in the format DD/MM/YYYY
    while True:
        user_date = input("Please enter date of the previous maintenance job (DD/MM/YYYY): ").strip()
        try:
            return datetime.strptime(user_date, "%d/%m/%Y").date()
        except ValueError:
            print("Sorry, you did not enter a valid date (use DD/MM/YYYY).")


def next_service_date(from_date: date | None = None) -> str:
    # Works out the next service date (same day next month).
    # If next month has fewer days, it just uses the last day of that month.
    d = from_date or date.today()

    y = d.year
    m = d.month + 1
    if m == 13:
        m = 1
        y += 1

    last_day = monthrange(y, m)[1]
    day = min(d.day, last_day)

    return f"{day:02d}/{m:02d}/{y}"


def cal_difference(prev: date, curr: date) -> int:
    # Number of days between the previous job and the current job
    return (curr - prev).days


def check_serial_num() -> str:
    # Makes sure the serial number is exactly 12 digits (numbers only)
    while True:
        print("###############################################")
        ser_num = input("Please enter the plane reference number (12 digits): ").strip()

        if len(ser_num) == 12 and ser_num.isdigit():
            return ser_num

        print("Please enter a valid 12 digit plane number (numbers only).")


def record_job_reason() -> str:
    # Menu for why the job is happening (we map the number to the tuple above)
    print("")
    print("################################################")
    print("#### Please choose a reason for current job ####")
    print("## 1. Annual service")
    print("## 2. Cosmetic repair")
    print("## 3. Mechanical repair - body")
    print("## 4. Mechanical repair - wing")
    print("## 5. Mechanical repair - engine")
    print("")

    while True:
        reason_choice = input("Enter reason choice here (1-5): ").strip()

        try:
            choice = int(reason_choice)
        except ValueError:
            print("Sorry, you did not enter a valid option number.")
            continue

        if 1 <= choice <= 5:
            # Choice is 1-5 but Python lists start at 0, so take 1 off
            return reason_list[choice - 1]

        print("Sorry, you did not choose an option within the given range (1-5).")


def record_job_outcomes(diff_days: int) -> str:
    # If the plane has had another job in the last 30 days, we force it grounded
    if diff_days < 30:
        job_outcome = "grounded - do not fly"
        print("")
        print("#############")
        print("WARNING!!!!!")
        print("#############")
        print("This plane has had more than one maintenance job in less than 30 days.")
        print("The plane must undergo a safety investigation.")
        print(f"Job outcome has been set to: {job_outcome}")
        return job_outcome

    # Otherwise we let the user pick the outcome
    print("")
    print("###############################################")
    print("######### Please choose a job outcome #########")
    print("## 1. Return to service")
    print("## 2. Further action(s) required")
    print("## 3. Grounded - do not fly")
    print("")

    while True:
        out_choice = input("Enter outcome choice here (1-3): ").strip()

        try:
            choice = int(out_choice)
        except ValueError:
            print("Sorry, you did not enter a valid option number.")
            continue

        if 1 <= choice <= 3:
            return outcome_list[choice - 1]

        print("Sorry, you did not choose an option within the given range (1-3).")


def get_job_time() -> float:
    # Time spent on the job in hours (decimal), e.g. 1.25 for 1 hour 15 mins
    # Also checks it’s in 0.25 steps so it matches the example
    while True:
        print("###############################################")
        print("Please enter the number of hours spent on the job to the nearest 1/4 hour as a decimal")
        print("e.g 1 hour 15 minutes = 1.25")
        print("")
        raw = input("Enter time spent here: ").strip()

        try:
            hours = float(raw)
        except ValueError:
            print("Sorry, you did not enter a time in a valid format.")
            continue

        if hours <= 0:
            print("Time must be greater than 0.")
            continue

        if round(hours * 4) != hours * 4:
            print("Please enter time in 0.25 hour increments (e.g., 1.00, 1.25, 1.50, 1.75).")
            continue

        print("Time value accepted!")
        return hours


def output_summary(name: str, sn: str, jr: str, jd: date, pjd: date, ts: float, jo: str, ns: str) -> None:
    # Prints the summary and also saves it to a text file so there’s a record
    print("#################################################")
    print("######### Maintenance system job summary ########")
    print("")
    print(f"Engineer Name: {name}")
    print(f"Plane Serial Number: {sn}")
    print(f"Reason for Job: {jr}")
    print(f"Date of Maintenance Job: {jd.strftime('%d/%m/%Y')}")
    print(f"Date of Previous Job: {pjd.strftime('%d/%m/%Y')}")
    print(f"Time spent on job: {ts:.2f} hours")
    print(f"Job outcome: {jo}")
    print(f"Date of next scheduled service: {ns}")
    print("")

    with open("job_summaries.txt", "a", encoding="utf-8") as f:
        f.write("#################################################\n")
        f.write("Maintenance system job summary\n")
        f.write(f"Engineer Name: {name}\n")
        f.write(f"Plane Serial Number: {sn}\n")
        f.write(f"Reason for Job: {jr}\n")
        f.write(f"Date of Maintenance Job: {jd.strftime('%d/%m/%Y')}\n")
        f.write(f"Date of Previous Job: {pjd.strftime('%d/%m/%Y')}\n")
        f.write(f"Time spent on job: {ts:.2f} hours\n")
        f.write(f"Job outcome: {jo}\n")
        f.write(f"Date of next scheduled service: {ns}\n")
        f.write("\n")


def main():
    print("#################################################")
    print("#### Welcome to Elanp Air Maintenance system ####")
    print("#################################################")
    print("")

    engineer_name = input("Please enter your name: ").strip()

    plane_serial_num = check_serial_num()
    job_reason = record_job_reason()

    job = get_job_date()
    previous_job_date = get_previous_date()

    # Quick sanity check so we don’t accept a date in the future
    while previous_job_date > job:
        print("Previous job date cannot be in the future. Try again.")
        previous_job_date = get_previous_date()

    difference = cal_difference(previous_job_date, job)

    time_spent = get_job_time()
    job_outcome = record_job_outcomes(difference)
    next_service = next_service_date(job)

    output_summary(
        engineer_name,
        plane_serial_num,
        job_reason,
        job,
        previous_job_date,
        time_spent,
        job_outcome,
        next_service
    )


if __name__ == "__main__":
    main()
